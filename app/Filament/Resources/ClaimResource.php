<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClaimResource\Pages;
use App\Models\Claim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ClaimResource extends Resource
{
    protected static ?string $model = Claim::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationGroup = 'Manajemen Laporan';
    protected static ?string $modelLabel = 'Klaim Barang';
    protected static ?string $pluralModelLabel = 'Klaim Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->label('Barang')
                    ->relationship('item', 'judul')
                    ->required(),
                Forms\Components\Select::make('claimer_id')
                    ->label('Penklaim')
                    ->relationship('claimer', 'name')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status Klaim')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('bukti_foto')
                    ->label('Bukti Foto')
                    ->image()
                    ->directory('claims')
                    ->visibility('public'),
                Forms\Components\Textarea::make('bukti')
                    ->label('Bukti / Ciri Khusus')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan Admin / Verifikator')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.judul')
                    ->label('Barang')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Claim $record) => 'Pemilik: ' . ($record->item?->user?->name ?? '-')),
                Tables\Columns\TextColumn::make('claimer.name')
                    ->label('Penklaim')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Claim $record) => ($record->claimer?->username ?? '') . ' (' . ($record->claimer?->role ?? '') . ')'),
                Tables\Columns\ImageColumn::make('bukti_foto')
                    ->label('Bukti')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('bukti')
                    ->label('Rincian Bukti')
                    ->limit(40)
                    ->tooltip(fn (Claim $record) => $record->bukti),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Klaim')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Claim $record) => $record->status === 'menunggu')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Klaim Ini?')
                    ->modalDescription('Menyetujui klaim ini akan mengubah status barang menjadi "diklaim" dan otomatis menolak semua klaim lain yang masih menunggu.')
                    ->action(function (Claim $record) {
                        DB::transaction(function () use ($record) {
                            $record->update(['status' => 'disetujui']);
                            Claim::where('item_id', $record->item_id)
                                ->where('id', '!=', $record->id)
                                ->where('status', 'menunggu')
                                ->update(['status' => 'ditolak']);
                            $record->item->update(['status' => 'diklaim']);
                        });

                        Notification::make()
                            ->title('Klaim berhasil disetujui')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Claim $record) => $record->status === 'menunggu')
                    ->form([
                        Forms\Components\Textarea::make('catatan')
                            ->label('Alasan Penolakan')
                            ->placeholder('Opsional: masukkan alasan bukti belum memadai'),
                    ])
                    ->action(function (Claim $record, array $data) {
                        $record->update([
                            'status' => 'ditolak',
                            'catatan' => $data['catatan'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Klaim ditolak')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClaims::route('/'),
            'create' => Pages\CreateClaim::route('/create'),
            'edit' => Pages\EditClaim::route('/{record}/edit'),
        ];
    }
}
